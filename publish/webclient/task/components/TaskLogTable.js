import React from "react";
import moment from "moment";
import DataTable from "react-data-table-component";

import api from "../../../services/api";
import TaskLogDetails from "./TaskLogDetails";
import TaskFilterHeader from "./TaskFilterHeader";
import { Link } from "@mui/icons-material";
import { debounce } from "lodash";

class TaskLogTable extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      loading: true,
      order_by: "created_at",
      direction: "desc",
      limit: 10,
      page: 1,
      totalItems: 0,
      records: [],
      filter: {},
    };
    this.loadDataDebounced = debounce(this.loadData, 500);
  }

  componentDidMount() {
    this.loadData();
  }

  async loadData() {
    const { order_by, limit, page, direction, filter } = this.state;
    this.setState({ loading: true });

    const filters = {};
    Object.keys(filter).forEach((f) => (filters[`filter[${f}]`] = filter[f]));

    const response = await api.task.list({
      sort: `${order_by}:${direction}`,
      limit,
      page,
      ...filters,
    });

    if (this.state.filter !== filter) {
      return;
    }

    const { items, totalItems } = response.data;

    this.setState({
      records: items,
      totalItems,
      loading: false,
    });
  }

  async onSort(column, sortDirection) {
    await this.setState({
      order_by: column.selector,
      direction: sortDirection,
    });
    this.loadData();
  }

  async onChangeRowsPerPage(currentRowsPerPage) {
    await this.setState({ limit: currentRowsPerPage });
    this.loadData();
  }

  async onChangePage(page, totalRows) {
    await this.setState({ page });
    this.loadData();
  }

  async onChangeFilter(filter) {
    await this.setState({ filter });
    this.loadDataDebounced();
  }

  render() {
    const { records, loading, totalItems, order_by, direction } = this.state;
    return (
      <div style={{ width: "98%", marginLeft: "auto" }}>
        <DataTable
          columns={[
            {
              name: "Started",
              sortable: true,
              selector: "created_at",
              cell: (row) => {
                const m = moment(row.created_at);
                return (
                  <div>
                    <span className={"text-muted"}>
                      {m.format("YYYY-MM-DD")}
                    </span>
                    <br />
                    <span className={"time"}>{m.format("HH:mm:ss Z")}</span>
                  </div>
                );
              },
            },
            { name: "Type", sortable: true, selector: "type" },
            {
              name: "Task",
              sortable: true,
              selector: "created_at",
              cell: (row) => {
                return <span>{row.entries[0]?.message || "no entry"}</span>;
              },
              grow: 4,
            },
            {
              name: "",
              sortable: false,
              selector: "id",
              cell: (row) => {
                const { id } = row;
                return (
                  <span
                    onClick={() =>
                      this.props.router.push(`/dashboard/tasks?id=${id}`)
                    }
                  >
                    <Link />
                  </span>
                );
              },
              grow: 0,
            },
          ]}
          data={records}
          noHeader
          subHeader
          subHeaderComponent={
            <TaskFilterHeader onChange={this.onChangeFilter.bind(this)} />
          }
          progressPending={loading}
          expandableRows
          expandableRowsComponent={TaskLogDetails}
          expandOnRowClicked
          onSort={this.onSort.bind(this)}
          defaultSortField={order_by}
          defaultSortAsc={direction}
          sortServer
          pagination
          paginationServer
          onChangePage={this.onChangePage.bind(this)}
          onChangeRowsPerPage={this.onChangeRowsPerPage.bind(this)}
          paginationTotalRows={totalItems}
          conditionalRowStyles={[
            {
              when: (row) => true,
              style: (row) => {
                let backgroundColor = "#ffffffff";
                switch (row.status) {
                  case "COMPLETED":
                    backgroundColor = "#edf9f1";
                    break;
                  case "NEW":
                    backgroundColor = "#edf2f9";
                    break;
                  case "IN_PROGRESS":
                    backgroundColor = "#efedf9";
                    break;
                  case "FAILED":
                    backgroundColor = "#f9F7ED";
                    break;
                  case "ERRORED":
                    backgroundColor = "#f9eded";
                    break;
                }

                return { backgroundColor };
              },
            },
          ]}
        />
      </div>
    );
  }
}

export default TaskLogTable;
