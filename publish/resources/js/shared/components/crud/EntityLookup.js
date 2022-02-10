import React, {useCallback} from "react";
import PropTypes from "prop-types";
import S from "string";
import {
  TextField,
  CircularProgress,
  withStyles
} from "@material-ui/core";
import {
  Autocomplete
} from "@material-ui/lab";
import api from "../../../services/api";


function EntityLookup(props) {
  const {
    classes,
    fieldName,
    idField,
    label,
    updateEntity,
    entity,
    errors,
    model,
    namespace,
    ...forwardProps
  } = props;

  const hasError = Boolean(errors && errors[fieldName]);

  const [open, setOpen] = React.useState(false);
  const [options, setOptions] = React.useState([]);
  const loading = open && options.length === 0;

  const searchModels = useCallback((lookup) => {
    let active = true;

    if (!loading) {
      return undefined;
    }

    (async () => {
      const args = {limit: 100};

      if(lookup){
        args[`filter[${fieldName}.${label}]`] = `like:${lookup}%`
      }
      const response = await api[namespace].list(args);
      if (active && response.data) {
        setOptions(response.data.items);
      }
    })();

    return () => {
      active = false;
    };
  }, [loading]);

  React.useEffect(() => {
    searchModels();
  }, [searchModels]);

  React.useEffect(() => {
    if (!open) {
      setOptions([]);
    }
  }, [open]);

  return (
    <Autocomplete
      id={idField}
      open={open}
      onOpen={() => {
        setOpen(true);
      }}
      onClose={() => {
        setOpen(false);
      }}
      value={entity[fieldName] || null}
      getOptionSelected={(option, value) => option.id === value}
      getOptionLabel={(option) => option[label]}
      onChange={(event, newValue) => {
        updateEntity(idField, newValue.id);
        updateEntity(fieldName, newValue);
      }}
      options={options}
      loading={loading}
      renderInput={(params) => (
        <TextField
          {...params}
          label={S(model).humanize().s}
          variant="outlined"
          onChange={(event) => {
            searchModels(event.target.value);
          }}
          InputProps={{
            ...params.InputProps,
            endAdornment: (
              <React.Fragment>
                {loading ? <CircularProgress color="inherit" size={20}/> : null}
                {params.InputProps.endAdornment}
              </React.Fragment>
            ),
          }}
        />
      )}
    />
  );
}

EntityLookup.propTypes = {
  fieldName: PropTypes.string.isRequired,
  label: PropTypes.string.isRequired,
  updateEntity: PropTypes.func.isRequired,
  entity: PropTypes.object,
  errors: PropTypes.object,
  type: PropTypes.string,
};


const styles = {
  inputStyle: {
    margin: "8px"
  }
};


export default withStyles(styles)(EntityLookup);
