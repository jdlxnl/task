import React, {
  useCallback,
  useRef,
  useState
} from "react";
import PropTypes from "prop-types";
import VisibilityPasswordTextField from "../VisibilityPasswordTextField";
import {withStyles} from "@material-ui/core";

function EntityPasswordField(props) {
  const {
    classes,
    fieldName,
    label,
    updateEntity,
    entity,
    errors,
    required,
    setError,
    clearError,
    mode,
    ...forwardProps
  } = props;

  const mismatchError = "The passwords do not match"
  const needsFill = required && mode === "create";

  const hasError = Boolean(errors && errors[fieldName]);
  let hasMismatchError = false;
  let otherError = false;

  if(hasError){
    const messages = [...errors[fieldName]];
    const matchIndex = messages.indexOf(mismatchError);
    if(matchIndex >= 0){
      messages.splice(matchIndex,1);
      hasMismatchError = true;
    }

    otherError = messages.length > 0 ? messages[0] : false;
  }


  const [isPasswordVisible, setIsPasswordVisible] = useState(false);

  const passwordField = useRef();
  const repeatField = useRef();

  const onBlur = useCallback( (main = false) => {
    const password = passwordField.current.value;
    const repeat = repeatField.current.value;
    const match = password === repeat;

    if(!match && main && repeat === ""){
      return;
    }

    if(!match){
      setError(fieldName, mismatchError);
    }else{
      clearError(fieldName, mismatchError);
    }
  }, [passwordField, repeatField, setError, clearError]);

  const onChange = useCallback(async () => {
    const password = passwordField.current.value;
    const repeat = repeatField.current.value;
    if (password === "" && repeat === "") {
      if(needsFill){
        updateEntity(fieldName, password);
      }else{
        updateEntity(fieldName, undefined);
      }
      return
    }

    if (password === repeat) {
      clearError(fieldName, mismatchError);
      updateEntity(fieldName, password);
      return
    }else{
      setError(fieldName, mismatchError);
    }
  }, [passwordField, repeatField, setError, updateEntity, clearError]);

  return (
    <>
      <VisibilityPasswordTextField
        variant="outlined"
        margin="normal"
        required={needsFill}
        fullWidth
        error={otherError}
        label="Password"
        inputRef={passwordField}
        autoComplete="off"
        onChange={() => {
          onChange();
        }}
        onBlur={() => onBlur(true)}
        isVisible={isPasswordVisible}
        onVisibilityChange={setIsPasswordVisible}
        helperText={otherError && otherError}
      />
      <VisibilityPasswordTextField
        variant="outlined"
        margin="normal"
        required={needsFill || Boolean(!needsFill && passwordField?.current?.value)}
        fullWidth
        error={hasMismatchError}
        label="Repeat Password"
        inputRef={repeatField}
        autoComplete="off"
        helperText={hasMismatchError && mismatchError}
        onChange={() => {
          onChange();
        }}
        onBlur={() => onBlur()}
        isVisible={isPasswordVisible}
        onVisibilityChange={setIsPasswordVisible}
      />
    </>
  );
}

EntityPasswordField.propTypes = {
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


export default withStyles(styles)(EntityPasswordField);
