import React        from "react";
import {
    Redirect,
    Route
}                   from "react-router";
import PropTypes from "prop-types";
import authProvider from "../../services/authProvider";

const renderMergedProps = (component, ...rest) => {
    const finalProps = Object.assign({}, ...rest);
    return React.createElement(component, finalProps);
};

const PrivateRoute =  ({ component, ...rest }) => {
    const authenticated = authProvider.checkAuth();
    if(!authenticated){
        console.warn("Unauthorized visit");
    }
    return (
        <Route {...rest} render={(routeProps) => (
            authenticated
                ? renderMergedProps(component, routeProps, rest)
                : <Redirect to='/'/>
        )}/>
    );
}


PrivateRoute.propTypes = {
    component: PropTypes.oneOfType([PropTypes.elementType, PropTypes.node])
};

export default PrivateRoute;
