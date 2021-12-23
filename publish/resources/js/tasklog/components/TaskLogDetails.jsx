import React from 'react';
import moment from "moment";
import ReactJson from 'react-json-view'
import ProgressIcon from "./ProgressIcon";
import ExpandToggle from "./ExpandToggle";

class TaskLogDetails extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            showPayload: false,
            showContext: {}
        };
    }

    toggleContext(entryIndex, status) {
        const showContext = {...this.state.showContext};
        showContext[entryIndex] = status;
        this.setState({showContext})
    }

    getStatus(severity, status, first, last) {
        if (first) {
            return "start";
        }
        console.log(severity, status, first, last);
        if (severity === "info" && status === "COMPLETED" && last) {
            return "complete";
        }

        if (severity === "info" && !last) {
            return "progress";
        }

        if (severity === "warn") {
            return "warning";
        }

        if (severity === "error") {
            return "failed";
        }

    }

    render() {
        const codeBg = {backgroundColor: "#121212"};

        const {data} = this.props;
        const {id, entries, created_at, updated_at, type, payload, status} = data;
        const {showPayload, showContext} = this.state;

        const started = moment(created_at);
        const ended = moment(entries.length ? [...entries].pop().created_at : updated_at);

        // get proper end time.
        // add label, comleted at, failed at etc ...
        return (
            <div className={"p-3 task-log"}>
                <div className={"card"}>
                    <div className="card-header d-flex align-items-center justify-content-between">
                        <h6>{type}</h6>
                    </div>
                    <div className="card-body card-bg-secondary">
                        <div className="row mb-2">
                            <div className="col-md-2"><strong>ID</strong></div>
                            <div className="col">{id}</div>
                        </div>
                        <div className="row mb-2">
                            <div className="col-md-2"><strong>Type</strong></div>
                            <div className="col">{type}</div>
                        </div>
                        <div className="row mb-2">
                            <div className="col-md-2"><strong>Started</strong></div>
                            <div className="col">{started.format()}</div>
                        </div>
                        <div className="row mb-2">
                            <div className="col-md-2"><strong>Ended</strong></div>
                            <div className="col">{ended.format()}</div>
                        </div>
                        <div className="row mb-2">
                            <div className="col-md-2"><strong>Link</strong></div>
                            <div className="col"><a href={`/horizon/tasklogs/${id}`}>{`/horizon/tasklogs/${id}`}</a></div>
                        </div>

                    </div>
                </div>

                <div className={"card  mt-4"}>
                    <div className="card-header d-flex align-items-center justify-content-between">
                        <h6>Log Entries</h6>
                    </div>

                    <div className="card-body card-bg-secondary">
                        <div className="table-responsive">
                            <table className="table mb-0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Message</th>
                                        <th>Context</th>
                                    </tr>
                                </thead>

                                {this.props.data.entries.map((e, i) => {
                                    const c = moment(e.created_at);
                                    const took = moment.utc(c.diff(started)).format("HH:mm:ss");
                                    const progress = this.getStatus(e.severity, status, i === 0, i + 1 === entries.length);
                                    const showMyContext = showContext[i] || false;

                                    return (
                                        <tbody key={e.id}>
                                            <tr key={e.id + "_record"}>
                                                <td className="card-bg-secondary">{<ProgressIcon type={progress}/>}</td>
                                                <td className="card-bg-secondary">
                                                    <div
                                                        className={"text-muted"}>Elapsed: {took}</div>
                                                    {e.message}
                                                </td>
                                                <td className="card-bg-secondary">{e.context ? <ExpandToggle
                                                    onChange={(showPayload) => {
                                                        this.toggleContext(i, showPayload)
                                                    }}
                                                    showIcon
                                                /> : ""}</td>
                                            </tr>
                                            {showMyContext &&
                                            <tr key={e.id + "_context"} style={codeBg}>
                                                <td className="card-bg-secondary" colspan={3} style={codeBg}>
                                                    <ReactJson
                                                        src={e.context}
                                                        theme={"colors"}
                                                    />
                                                </td>
                                            </tr>
                                            }
                                        </tbody>);
                                })}
                            </table>
                        </div>
                    </div>
                </div>


                <div className={"card  mt-4"}>
                    <div className="card-header d-flex align-items-center justify-content-between">
                        <h6>Payload</h6>
                        {payload && <ExpandToggle
                            onChange={(showPayload) => {
                                this.setState({showPayload})
                            }}
                            showIcon
                        />}
                    </div>

                    {!payload &&
                    <div className="card-body card-bg-secondary">
                        No Payload
                    </div>
                    }
                    {showPayload && payload &&
                    <div className="card-body" style={codeBg}>
                        <ReactJson
                            src={payload}
                            theme={"colors"}
                        />
                    </div>
                    }
                </div>


            </div>
        );
    }
}

export default TaskLogDetails;
