import React, { useEffect, useState } from "react";
import Head from "next/head";
import { useRouter } from "next/router";
import { Box, Container, Grid, Typography } from "@mui/material";
import { AuthGuard } from "../../../../../components/authentication/auth-guard";
import { DashboardLayout } from "../../../../../components/dashboard/dashboard-layout";
import TaskLogTable from "../../../components/TaskLogTable";
import TaskLogDetails from "../../../components/TaskLogDetails";
import api from "../../../../../services/api";

const Tasks = () => {
  const router = useRouter();

  const [taskDetails, setTaskDetails] = useState();

  useEffect(() => {
    const getData = async (id) => {
      try {
        const response = await api.taskLog.get(id);
        setTaskDetails(response.data);
      } catch (error) {
        console.log("error", error);
      }
    };
    if (router.query.id) {
      getData(router.query.id);
    }
  }, [router.query]);

  return (
    <>
      <Head>
        <title>TaskLog: Overview</title>
      </Head>
      <Box
        component="main"
        sx={{
          flexGrow: 1,
          py: 8,
        }}
      >
        <Container maxWidth="xl">
          <Typography color="textPrimary" variant="h5" marginBottom={5}>
            {`Task Log${router.query.id ? ` id: ${router.query.id}` : "s"}`}
          </Typography>
          <Grid container spacing={4}>
            {router.query.id && taskDetails ? (
              <TaskLogDetails data={taskDetails} fullWidth />
            ) : router.query.id ? (
              <div
                style={{
                  marginLeft: "auto",
                  marginRight: "auto",
                  marginTop: 100,
                }}
              >
                Loading...
              </div>
            ) : (
              <TaskLogTable router={router} />
            )}
          </Grid>
        </Container>
      </Box>
    </>
  );
};

Tasks.getLayout = (page) => (
  <AuthGuard>
    <DashboardLayout>{page}</DashboardLayout>
  </AuthGuard>
);

export default Tasks;
