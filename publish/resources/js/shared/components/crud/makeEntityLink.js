import S from "string";

export default (namespace, entity, mode = "view") => {
  let path = S(namespace).dasherize().s;
  let link = `/admin/${path}/${entity.id}`;
  if (mode === "edit") {
    link += "/edit";
  }
  return link;
}
