import React, {
  useCallback,
  useEffect,
  useRef,
  useState
} from "react";
import PropTypes from "prop-types";
import {
  Snackbar,
  withStyles
} from "@material-ui/core";
import {
  Alert,
  AlertTitle
} from '@material-ui/lab';

const styles = (theme) => ({
  root: {
    border: "none",
    backgroundColor: "unset",
    boxShadow: "none",
    paddingTop: 0,
    paddingBottom: 0,
  },
});

function ConsecutiveSnackbars(props) {
  const {classes, getPushMessageFromChild} = props;
  const [isOpen, setIsOpen] = useState(false);
  const [messageInfo, setMessageInfo] = useState({});
  const queue = useRef([]);

  const processQueue = useCallback(() => {
    if (queue.current.length > 0) {
      setMessageInfo(queue.current.shift());
      setIsOpen(true);
    }
  }, [setMessageInfo, setIsOpen, queue]);

  const handleClose = useCallback((_, reason) => {
    if (reason === "clickaway") {
      return;
    }
    setIsOpen(false);
  }, [setIsOpen]);

  const pushMessage = useCallback(message => {
    queue.current.push({
      message,
      key: new Date().getTime(),
    });
    if (isOpen) {
      // immediately begin dismissing current message
      // to start showing new one
      setIsOpen(false);
    } else {
      processQueue();
    }
  }, [queue, isOpen, setIsOpen, processQueue]);

  useEffect(() => {
    getPushMessageFromChild(pushMessage);
  }, [getPushMessageFromChild, pushMessage]);

  return (
    <Snackbar
      disableWindowBlurListener
      key={messageInfo.key}
      anchorOrigin={{
        vertical: "top",
        horizontal: "center",
      }}
      open={isOpen}
      autoHideDuration={6000}
      onClose={handleClose}
      onExited={processQueue}
    >
      <Alert
        severity={messageInfo.message?.severity}
        elevation={2}
      >
        {messageInfo.message?.title && <AlertTitle>{messageInfo.message.title}</AlertTitle>}
        {messageInfo.message ? messageInfo.message.text : null}
      </Alert>
    </Snackbar>
  );

}

ConsecutiveSnackbars.propTypes = {
  getPushMessageFromChild: PropTypes.func.isRequired,
  classes: PropTypes.object.isRequired,
};

export default withStyles(styles, {withTheme: true})(ConsecutiveSnackbars);
