import React, { useCallback, useState } from "react";

import { ExpandMore, ExpandLess } from "@mui/icons-material";

const ExpandToggle = ({
  initialState = false,
  onChange,
  showLabel = false,
  showIcon = true,
}) => {
  const [expanded, setExpanded] = useState(initialState);
  const onClick = useCallback(() => {
    const next = !expanded;
    setExpanded(next);
    onChange(next);
  }, [expanded, setExpanded, onChange]);

  const iconStyle = {};
  const label = expanded ? "Collapse" : "Expand";
  const icon = expanded ? (
    <ExpandLess style={iconStyle} />
  ) : (
    <ExpandMore style={iconStyle} />
  );

  return (
    <button className={"btn btn-outline-primary btn-sm"} onClick={onClick}>
      {showIcon && icon}
      {showLabel && label}
    </button>
  );
};

export default ExpandToggle;
