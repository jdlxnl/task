import React, {useCallback} from "react";
import PropTypes from "prop-types";
import {JsonEditor as Editor} from 'jsoneditor-react';
import 'jsoneditor-react/es/editor.min.css';
import {
  Typography,
  withStyles
} from "@material-ui/core";

function EntityJsonEditor(props) {
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

  const onChange = useCallback((value) => {
    try {
      if (value === null) {
        updateEntity(fieldName, null);
      }
      updateEntity(fieldName, value);
    } catch (e) {
      console.log(e);
      console.log("well that didn't work");
    }
  }, [updateEntity]);
  return (
    <>
      <Typography id="discrete-slider" gutterBottom>
        {label}
      </Typography>
      <Editor
        value={entity[fieldName] || null}
        onChange={onChange}
        mode={'code'}
      />
    </>
  );
}

EntityJsonEditor.propTypes = {
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


export default withStyles(styles)(EntityJsonEditor);
