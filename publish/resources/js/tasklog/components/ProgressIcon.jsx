import React from 'react';
import {
    FiCheckSquare,
    FiInfo,
    FiAlertOctagon,
    FiPlayCircle,
    FiAlertTriangle
} from "react-icons/fi";

const ProgressIcon = ({type} ) => {
    switch (type){
        case "start": return (<FiPlayCircle style={{color: "#406bcc", fontSize: "1.2em"}} />);
        case "complete": return (<FiCheckSquare style={{color: "#56cc40", fontSize: "1.2em"}} />);
        case "warning": return (<FiAlertTriangle style={{color: "#f1e354", fontSize: "1.2em"}} />);
        case "failed": return (<FiAlertOctagon style={{color: "#cc404d", fontSize: "1.2em"}} />);
        default: return (<FiInfo style={{color: "#888", fontSize: "1.2em"}} />);
    }
}

export default ProgressIcon;
