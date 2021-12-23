import React, {
    useCallback,
    useState
} from 'react';
import {
    Col,
    Form
} from 'react-bootstrap';
import Select from 'react-select';
import moment from "moment";

const TaskFilterHeader = ({onChange}) => {

    const [filter, setFilter] = useState();

    const [states, setStates] = useState([
        {label: 'Errored', value: 'ERRORED', selected: true},
        {label: 'Failed', value: 'FAILED', selected: true},
        {label: 'Completed', value: 'COMPLETED', selected: true},
        {label: 'New', value: 'NEW', selected: true},
        {label: 'In Progress', value: 'IN_PROGRESS', selected: true},
    ]);

    const [timeFrames, setTimeFrames] = useState([
        {label: '1 week', value: '1', selected: true},
        {label: '2 weeks', value: '2', selected: false},
        {label: '4 weeks', value: '4', selected: false},
        {label: '8 weeks', value: '8', selected: false},
        {label: '16 weeks', value: '16', selected: false},
        {label: '32 weeks', value: '32', selected: false},
        {label: 'forever', value: 'forever', selected: false},
    ]);

    const onTimeFrameChange = useCallback(({value}) => {
        let next = {...filter};

        if (!value || value === 'forever') {
            delete next.created_at;
        } else {
            next.created_at = "gt:" + moment().subtract(value, "weeks").toISOString();
        }
        setFilter(next);
        onChange(next);
    }, [filter, setFilter]);

    const onStateChange = useCallback((value, selected) => {
        let next = {...filter};

        if (!value || value.length === 0) {
            delete next.status;
        } else {
            let states = value.map(s => s.value);
            next.status = "in:" + states.join(",");
        }
        setFilter(next);
        onChange(next);
    }, [states, setStates, filter, setFilter]);

    const onFilterChange = useCallback((value, field, op = "eq") => {
        let next = {...filter};
        console.log(value, field);
        if (!value || value.length === 0) {
            delete next[field];
        } else {
            if(op === "like"){
                value = `%${value}%`;
            }
            next[field] = `${op}:${value}`;
        }
        setFilter(next);
        onChange(next);
    }, [filter, setFilter]);


    return (
        <Form>
            <Form.Row>
                <Col>
                    <Select
                        placeholder="Within weeks"
                        options={timeFrames}
                        onChange={onTimeFrameChange}
                        simpleValue
                    />
                </Col>
                <Col>
                    <Form.Control
                        type="contains"
                        placeholder="Search in log"
                        onChange={(e) => {
                            onFilterChange(e.target.value, 'task_log_entries.message', 'like')
                        }}

                    />
                </Col>

                <Col>
                    <Form.Control
                        type="contains"
                        placeholder="Task Type"
                        onChange={(e) => {
                            onFilterChange(e.target.value, 'type')
                        }}
                    />

                </Col>

                <Col>
                    <Select
                        placeholder="Select states"
                        options={states}
                        onChange={onStateChange}
                        isMulti
                        simpleValue
                    />
                </Col>
            </Form.Row>
        </Form>);
}

export default TaskFilterHeader;
