import React from "react";
import DateFnsUtils from '@date-io/date-fns';
import PropTypes from "prop-types";
import {withStyles} from "@material-ui/core";
import {
  DateTimePicker,
  MuiPickersUtilsProvider
} from "@material-ui/pickers";

function EntityDatePicker(props) {
  console.log(props);
  const {
    classes,
    fieldName,
    label,
    updateEntity,
    entity,
    errors,
    type,
    ...forwardProps
  } = props;

  const hasError = Boolean(errors && errors[fieldName]);

  return (
    <MuiPickersUtilsProvider utils={DateFnsUtils}>
      <DateTimePicker
        id={fieldName}
        label={label}
        margin="normal"
        inputVariant="outlined"
        value={entity[fieldName] || ''}
        onChange={date => updateEntity(fieldName, date)}
        fullWidth
        KeyboardButtonProps={{
          'aria-label': 'change time',
        }}
        error={hasError}
        helperText={hasError && errors[fieldName][0]}
        className={classes.inputStyle}
        {...forwardProps}
      />
    </MuiPickersUtilsProvider>
  );
}

EntityDatePicker.propTypes = {
  fieldName: PropTypes.string.isRequired,
  label: PropTypes.string.isRequired,
  updateEntity: PropTypes.func.isRequired,
  entity: PropTypes.object,
  errors: PropTypes.object,
  type: PropTypes.string,
};


const styles = {
  inputStyle: {}
};


export default withStyles(styles)(EntityDatePicker);
