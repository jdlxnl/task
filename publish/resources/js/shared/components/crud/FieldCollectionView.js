import React from "react";
import {
  Icon,
  List,
  ListItem,
  ListItemIcon,
  ListItemText,
  withStyles
} from "@material-ui/core";
import moment from "moment";
import {JsonEditor as Editor} from "jsoneditor-react";
import gotoEntity from "./gotoEntity";
import {useHistory} from "react-router";

function FieldCollectionView(props) {
  const {
    classes,
    fields,
    entity
  } = props;

  const history = useHistory();

  const iconLookup = (type, name) => {
    name = name.toLowerCase();
    if (type === "json") {
      return "code";
    }
    if (type === "email") {
      return "email";
    }
    if (type === "boolean") {
      return "fact_check";
    }
    if (type === "timestamp") {
      return "calendar_today";
    }
    if (type === "belongsTo") {
      return "link";
    }
    return "info";
  };

  const getField = (field) => {
    const {label, name, type} = field;
    const value = entity[name];
    const icon = field.icon || iconLookup(type, name);

    switch (type) {
      case "boolean":
        return (<IconListItem icon={icon} label={label} value={value ? label : `Not ${label}`} key={field.name}/>);
      case "json":
        return (
          <div key={field.name}>
            <ListItem>
              <ListItemIcon>
                <Icon>{icon}</Icon>
              </ListItemIcon>
              <ListItemText secondary={label}/>
            </ListItem>
            <Editor value={value} mode={'view'} fullwidth/>
          </div>
        );
      case "morphTo":
        // Make a link
        // admin/[S(name).underscore().s]/entity[field.nameSpace][id]

        let val = value === null ? null :  entity[field.namespace]['name'];
        let entType = entity[field.namespace + "_type"];
        let model = entType ? entType.split("\\").pop().toLowerCase() : null;

        return (
          <ListItem
            key={field.name}
            button
            onClick={() => gotoEntity(model, entity[field.namespace], history)}
          >
            <ListItemIcon>
              <Icon>{icon}</Icon>
            </ListItemIcon>
            <ListItemText primary={val} secondary={label}/>
          </ListItem>
        );
      case "belongsTo":
        // Make a link
        // admin/[S(name).underscore().s]/entity[field.nameSpace][id]
        return (
          <ListItem
            key={field.name}
            button
            onClick={() => gotoEntity(field.namespace, entity[field.namespace], history)}
          >
            <ListItemIcon>
              <Icon>{icon}</Icon>
            </ListItemIcon>
            <ListItemText primary={entity[field.namespace]['name']} secondary={label}/>
          </ListItem>
        );
      case "timestamp":
        const dateVal = moment(value).format("dddd, MMMM Do YYYY, h:mm:ss a");
        return (
          <IconListItem icon={icon} label={label} value={dateVal} key={field.name}/>);
      case "email":
      case "password":
      case "text":
      case "string":
      case "int":
      case "float":
      default:
        return (<IconListItem icon={icon} label={label} value={value} key={field.name}/>);
    }
  };

  const IconListItem = ({value, label, icon}) => (
    <ListItem>
      <ListItemIcon>
        <Icon>{icon}</Icon>
      </ListItemIcon>
      <ListItemText primary={value} secondary={label}/>
    </ListItem>
  );

console.log(fields);
  return (
    <List className={classes.container}>
      {Object
        .values(fields)
        .filter(field => !field.writeOnly)
        .map(field => getField(field))}
    </List>
  );
}

FieldCollectionView.propTypes = {};


const styles = {
  container: {
    width: "100%"
  }
};


export default withStyles(styles)(FieldCollectionView);
