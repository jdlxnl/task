import React, {
  useCallback,
  useContext,
  useState
} from "react";
import {
  Box,
  Button,
  CircularProgress,
  Grid,
  Icon,
  withStyles
} from "@material-ui/core";

import S from "string";
import TextField from "./EntityTextField";
import PasswordConfirm from "./EntityPasswordField";
import CheckBox from "./EntityCheckBox";
import JsonEditor from "./EntityJsonEditor";
import EntityLookup from "./EntityLookup";
import DatePicker from "./EnityDatePicker";

import {GlobalContext} from "../../../admin/GlobalContext";

function CreateUpdateForm(props) {
  const {
    classes,
    crudApi,
    mode,
    fields,
    defaultValues,
    preSubmit,
    onSaved,
    onFieldChange,
  } = props;


  const {pushMessageToSnackbar} = useContext(GlobalContext);
  const [entity, setEntity] = useState({...defaultValues});
  const [errors, setErrors] = useState({});
  const [saving, setSaving] = useState(false);

  const onSave = useCallback((event) => {
    const save = async () => {
      if (saving) {
        return;
      }

      setSaving(true);

      if (preSubmit) {
        const errors = await preSubmit(entity);
        if (errors) {
          setErrors(errors);
          setSaving(false);
          return;
        }
      }

      let res = null;
      if (mode === "create") {
        res = await crudApi.create(entity);
      } else {
        res = await crudApi.update(entity.id, entity);
      }

      setSaving(false);
      if (res.error && res.error.code === 422) {
        setErrors(res.error.errors);
      } else if (res.message || res.error) {
        pushMessageToSnackbar({title: 'Unexpected failure while saving', text: res.message, severity: "error"});
      } else {
        pushMessageToSnackbar({text: "Saved", severity: "success"});
        if (onSaved) {
          onSaved(res.result);
        }
      }
      return;
    };

    event.preventDefault();
    save();
    return false;
  }, [saving, setSaving, entity, setErrors, pushMessageToSnackbar]);

  const setError = useCallback((field, error) => {
    const setError = (field, error) => {
      if (!errors[field]) {
        errors[field] = [];
      }

      if (errors[field].indexOf(error) < 0) {
        errors[field].push(error);
        setErrors({...errors});
      }
    };

    setError(field, error);
  }, [errors, setErrors]);

  const clearError = useCallback((field, error) => {
    const clearError = (field, error) => {
      if (!errors[field]) {
        return;
      }
      if (!error) {
        delete errors[field];
      } else {
        errors[field].splice(errors[field].indexOf(error), 1);
      }
      setErrors({...errors});
    };

    clearError(field, error);
  }, [errors, setErrors]);


  const onUpdateValue = useCallback((field, value) => {
    const update = async (field, value) => {
      // Allow parent override validation
      // and manipulation of values
      if (onFieldChange) {
        const approved = await onFieldChange(field, value, entity, errors);
        if (!approved) {
          setEntity({...entity});
          setErrors({...errors});
          return;
        }
      }

      let newEntity = {...entity};

      // Set the value and clear the error
      if (value === undefined) {
        delete (newEntity[field]);
      } else {
        newEntity[field] = value;
      }

      if (errors[field]) {
        delete errors[field];
        setErrors({...errors});
      }
      setEntity(newEntity);
    };

    update(field, value);
  }, [entity, setEntity, errors, setErrors, onFieldChange]);


  const getField = (field) => {
    const {label, name, required, type} = field;

    const general = {
      label,
      fieldName: name,
      updateEntity: onUpdateValue,
      errors: errors,
      entity,
      required,
    };

    const errorControl = {
      setError: setError,
      clearError: clearError,
    };

    switch (field.type) {
      case "password":
        return (<PasswordConfirm mode={mode}  {...errorControl} {...general} />);
      case "boolean":
        return (<CheckBox {...general} />);
      case "json":
        return (<JsonEditor {...general} />);
      case "belongsTo":
        const idField = S(name).underscore().s + "_id";
        entity[idField] = entity[name]?.id;
        return (<EntityLookup {...general}
                              namespace={field.namespace}
                              idField={idField}
                              model={field.model}/>);
      case "timestamp":
        return (<DatePicker {...general} />);
      case "text":
      case "string":
      case "email":
      case "int":
      case "float":
      default:
        return (<TextField type={type} {...general} />);
    }
  };

  return (

    <form onSubmit={onSave}>
      <Grid container direction={"column"}>
        <>
          {fields
            .filter(field => field.editable)
            .map(field => {
              return (
                <Grid item key={field.name}>
                  {getField(field)}
                </Grid>
              );
            })}
        </>
        <Box className={classes.actions}>
          <Button
            variant={'contained'}
            color="primary"
            disabled={saving}
            type={"submit"}
          >
            {saving && (<><CircularProgress size={16}/> Saving</>)}
            {!saving && (<><Icon>save</Icon> Save</>)}
          </Button>
        </Box>
      </Grid>
    </form>
  );
}

CreateUpdateForm.propTypes = {};


const styles = {
  toolbar: {
    justifyContent: "space-between",
  },
  container: {
    minHeight: "50px",
    padding: "20px"
  },
  divider: {
    backgroundColor: "rgba(0, 0, 0, 0.26)"
  },
  actions: {
    textAlign: "center",
    padding: "20px 0px"
  }
};


export default withStyles(styles)(CreateUpdateForm);
