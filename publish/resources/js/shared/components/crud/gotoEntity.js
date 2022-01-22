import makeEntityLink from "./makeEntityLink";

export default (namespace, entity, history, mode = "view") => {
  history.push(makeEntityLink(namespace, entity, mode));
}
