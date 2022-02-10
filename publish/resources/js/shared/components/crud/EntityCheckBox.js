import React from "react";
import PropTypes from "prop-types";
import {
  TextField,
  withStyles,
  FormControlLabel,
  Checkbox
} from "@material-ui/core";

function EntityCheckBox(props) {
  const {
    classes,
    fieldName,
    label,
    updateEntity,
    entity,
    errors,
    ...forwardProps
  } = props;

  const hasError = Boolean(errors && errors[fieldName]);
  const checked = Boolean(entity[fieldName]);

  return (
    <FormControlLabel
      control={
        <Checkbox
          checked={checked}
          onChange={event => updateEntity(fieldName, event.target.checked)}
          name={fieldName}
          color="primary"
          className={classes.inputStyle}
          {...forwardProps}
        />
      }
      label={label}
    />
  );
}

EntityCheckBox.propTypes = {
  fieldName: PropTypes.string.isRequired,
  label: PropTypes.string.isRequired,
  updateEntity: PropTypes.func.isRequired,
  entity: PropTypes.object,
  errors: PropTypes.object,
  type: PropTypes.string,
};


const styles = {
  inputStyle: {

  }
};


export default withStyles(styles)(EntityCheckBox);
