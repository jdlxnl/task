import React from "react";
import PropTypes from "prop-types";
import {
  TextField,
  withStyles
} from "@material-ui/core";

function EntityTextField(props) {
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

  return (
    <TextField
      error={hasError}
      id={fieldName}
      label={label}
      fullWidth
      margin="normal"
      variant="outlined"
      onChange={event => updateEntity(fieldName, event.target.value)}
      value={entity[fieldName] || ''}
      InputLabelProps={{
        shrink: true,
      }}
      helperText={hasError && errors[fieldName][0]}
      className={classes.inputStyle}
      {...forwardProps}
    />
  );
}

EntityTextField.propTypes = {
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


export default withStyles(styles)(EntityTextField);
