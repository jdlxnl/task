import React, {memo} from "react";
import {Route} from "react-router";
import {Switch} from "react-router-dom";
import TaskLogList from "./view/TasklogList";
import Tasklog from "./view/Tasklog";
import NotFound from "./view/NotFound";

function Routing({location}) {
    return (
            <Switch>
                <Route exact path="/horizon/tasklogs" component={TaskLogList}/>
                <Route exact path="/horizon/tasklogs/:id" component={Tasklog}/>
                <Route path="*" component={NotFound}/>
            </Switch>
    );
}

Routing.propTypes = {};

export default memo(Routing);
