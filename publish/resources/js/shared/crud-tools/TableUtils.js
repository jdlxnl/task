import React from "react";
import S from "string";
import EntityLink from "../components/crud/EntityLink";

export default class TableUtils {
  static baseConfig(entity, fields, api, history, extend = {}, defaultData = null) {
    const config = {
      title: S(entity).humanize().ensureRight('s').s,
      options: {
        actionsColumnIndex: -1,
        //showTitle: false,
        sorting: true,
        pageSize: 10,
        pageSizeOptions: [10, 25, 50, 100]
      },
      actions: [
        {
          icon: 'visibility',
          tooltip: `View ${entity}`,
          onClick: (event, rowData) => {
            event.preventDefault()

            history.push(`/admin/${entity}/${rowData.id}`);
          },
        },
        {
          icon: 'edit',
          tooltip: `Edit ${entity}`,
          onClick: (event, rowData) => {
            history.push(`/admin/${entity}/${rowData.id}/edit`);
          }
        },
        {
          icon: 'add',
          tooltip: 'Add ${entity}',
          isFreeAction: true,
          onClick: (event) => {
            history.push(`/admin/${entity}/new`);
          }
        }
      ],
      editable: {
        onRowDelete: oldData => api.delete(oldData.id)
      },
      cellEditable: {
        onCellEditApproved: async (newValue, oldValue, rowData, columnDef) => {
          await api.update(rowData.id, {[columnDef.field]: newValue});
          rowData[columnDef.field] = newValue;
          return;
        }
      },
      columns: this.fieldsFromModel(fields),
      data: async (query) => {
        if(defaultData !== null) {
          return { data: defaultData }
        } else {
          const result = await api.list(this.buildPagingParameters(query));
          if (result.data) {
            return {
              data: result.data.items,
              page: result.data.pageIndex - 1,
              totalCount: result.data.totalItems,
            };
          } else {
            throw new Error(result.error.message);
          }
        }
      }
    };
    return {...config, ...extend};
  }

  static viewConfig(data, fields,  extend = {}) {
    const config = {
      options: {
        actionsColumnIndex: -1,
        showTitle: false,
        search: false,
        sorting: true,
        // pageSize: 100,
        // pageSizeOptions: [10, 25, 50, 100]
        paging: false
      },

      columns: this.fieldsFromModel(fields),
      data
    };
    return {...config, ...extend};
  }

  static fieldsFromModel(fields) {
    return fields
      .filter(f => !f.writeOnly)
      .map((field) => {
        const {label, name, type, editable, sortable} = field;

        const conf = {
          title: label,
          field: name,
          sorting: sortable,
          editable: editable ? 'always':'never'
        };

        switch (type) {
          case "belongsTo":
            conf.title = field.model;
            conf.field = field.namespace + "." + label;
            conf.editable = "never";
            conf.render= (data) => (<EntityLink namespace={field.namespace} entity={data[field.namespace]} />);
            break;
          case "morphTo":
            conf.title = field.model;
            conf.field = field.namespace + "." + label;
            conf.editable = "never";
            conf.render= (data) => (<EntityLink namespace={data[field.namespace + "_type"]} entity={data[field.namespace]} />);
            break;
          case "currency":
            conf.type = "currency";
            break;
          case "date":
            conf.type = "date";
            break;
          case "time":
            conf.type = "time";
            break;
          case "datetime":
          case "timestamp":
            conf.type = "datetime";
            break;
          case "boolean":
            conf.type = "boolean";
            break;
          case "int":
          case "float":
            conf.type = "numeric";
            break;
          case "json":
            conf.type = "string";
            conf.editable = 'never';
            // onclick ,
            // use a modal, populate the data on the modal
            // use manual trigger to update thingy
            conf.render = (data) => this.renderJson(data, name);
            break;
          case "text":
          case "string":
          case "email":
          default:
        }

        return conf;
      });
  }

  static renderJson(data, name) {
    const val = JSON.stringify(data[name]);
    if(val.length > 50){
      return `{ ... ${val.length} }`;
    }
    return val;
  }

  static buildPagingParameters(query) {
    let fromQueryString = {}

    let urlQueries = ['orderBy','pageSize','page','orderDirection']

    urlQueries.forEach((item) => {
        fromQueryString[item] = this.getParameterByName(item)
    })

    let merged = {...query,...fromQueryString}

    let {
      // error,
      filters,
      orderBy,
      orderDirection,
      page,
      pageSize,
      // search
    } = merged;

    let parameters = {
      page: page + 1,
      limit: pageSize,
    };

    if (orderBy) {

      // check if query has orderDirection
      // if it's null, take from url
      if(query.orderDirection !== '') {
        orderDirection = query.orderDirection
      }
      let orderByWith = orderBy['field'] || orderBy

      parameters.sort = `${orderByWith}:${orderDirection}`;
    }

    // todo handle filters from url
    if (filters) {
      filters.forEach(filter => {
        switch (filter.column.type) {
          case "datetime":
            parameters[filter.column.field] = `gt:${filter.value}`;
            break;
          case "boolean":
            parameters[filter.column.field] = filter.value === "checked";
            break;
          default:
            parameters[filter.column.field] = `like:%${filter.value}%`;
            break;
        }
      });
    }
    // todo add this to url ?
    return parameters;
  }
  static getParameterByName(name) {
    name = name.replace(/[\[\]]/g, '\\$&');
    let regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
      results = regex.exec(window.location.href);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
  }
}
