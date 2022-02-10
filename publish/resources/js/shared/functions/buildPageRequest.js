import toArray from "./toArray";

/**
 * Counts the characters in a string and counts emojis correctly.
 *
 * @param {string} str The string to count characters from.
 * @return {number} The number of characters in the string.
 */
function buildPagingParameters(query) {
  const {
    // error,
    // filters,
    orderBy,
    orderDirection,
    page,
    pageSize,
    // search
  } = query;

  const  parameters = {
    page: query.page + 1,
    limit: query.pageSize,
  };

  if(orderBy){
    parameters.sort = `${orderBy.field}:${orderDirection}`;
  }

  return parameters;
}

export default buildPagingParameters;
