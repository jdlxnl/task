import React from 'react';
import TaskLogTable from "../components/TaskLogTable";

class TaskLogList extends React.Component {
    constructor(props) {
        super(props);
    }

    render(){
        return (
            <div className="card">
                <div className="card-header d-flex align-items-center justify-content-between">
                    <h5>Task Logs</h5>
                </div>
                <div
                    className="d-flex flex-column align-items-center justify-content-center card-bg-secondary bottom-radius">
                    <TaskLogTable />
                </div>
            </div>
        );
    }
}

export default TaskLogList;
