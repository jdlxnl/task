import React, {
    useCallback,
    useState
} from 'react';

import {
    AiOutlinePlus,
    AiOutlineMinus
} from "react-icons/ai";

const ExpandToggle = ({initialState = false, onChange, showLabel = false, showIcon = true}) => {

    const [expanded, setExpanded] = useState(initialState);
    const onClick = useCallback(() => {
        const next = !expanded;
        setExpanded(next);
        onChange(next);
    }, [expanded, setExpanded, onChange]);


    const iconStyle = {
    };
    const label = expanded ? "Collapse" : "Expand";
    const icon = expanded ? (<AiOutlineMinus style={iconStyle}/>) : (<AiOutlinePlus style={iconStyle}/>);

    return (<button className={"btn btn-outline-primary"} onClick={onClick}>
        {showIcon && icon}{showLabel && label}
    </button>);
}

export default ExpandToggle;
