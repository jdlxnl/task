import React from "react";
import makeEntityLink from "./makeEntityLink";
import {Link} from "react-router-dom";

export default ({entity, namespace, mode = "view"}) => {
  if(!entity){
    return '-';
  }

  namespace = namespace.replace(/^App\\/, "");
  namespace = namespace.charAt(0).toLowerCase() + namespace.slice(1);

  const label = entity.label || entity.name || entity.title || entity.id;
  return (
    <Link
      to={makeEntityLink(namespace, entity, mode)}
    >{label}</Link>
  )
}



