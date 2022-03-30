import React from "react";
import {
  CheckCircle,
  Info,
  Warning,
  PlayCircleOutline,
  Report,
} from "@mui/icons-material";

const ProgressIcon = ({ type }) => {
  switch (type) {
    case "start":
      return (
        <PlayCircleOutline
          style={{
            color: "#406bcc",
            fontSize: "1.2em",
            marginTop: 13,
          }}
        />
      );
    case "complete":
      return (
        <CheckCircle
          style={{
            color: "#56cc40",
            fontSize: "1.2em",
            marginTop: 13,
          }}
        />
      );
    case "warning":
      return (
        <Warning
          style={{
            color: "#f1e354",
            fontSize: "1.2em",
            marginTop: 13,
          }}
        />
      );
    case "failed":
      return (
        <Report
          style={{
            color: "#cc404d",
            fontSize: "1.2em",
            marginTop: 13,
          }}
        />
      );
    default:
      return (
        <Info
          style={{
            color: "#888",
            fontSize: "1.2em",
            marginTop: 13,
          }}
        />
      );
  }
};

export default ProgressIcon;
