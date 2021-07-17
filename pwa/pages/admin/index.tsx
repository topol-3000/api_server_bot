import Head from "next/head";
import authProvider from "utils/authProvider";
import Login from "components/admin/Login";

const AdminLoader = () => {
  if (typeof window !== "undefined") {
    const { HydraAdmin } = require("@api-platform/admin");
    return <HydraAdmin authProvider={authProvider} entrypoint={window.origin} loginPage={Login} />;
  }

  return <></>;
};

const Admin = () => (
  <>
    <Head>
      <title>API Platform Admin</title>
    </Head>

    <AdminLoader />
  </>
);
export default Admin;
