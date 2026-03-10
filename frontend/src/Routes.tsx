import { Route, Routes } from "react-router-dom";
import Login from "./login/Login";
import Layout from "./Layout";
import Dashboard from "./features/dashboard/Dashboard";
import Profile from "./features/profile/Profile";

export default function AppRoutes(){
  return(
    <>
      <Routes>
        <Route element={<Layout />}>
          <Route path='/dashboard' element={<Dashboard />}></Route>
          <Route path='/profile' element={<Profile />}></Route>
        </Route>
          <Route path='/login' element={<Login />}></Route>
      </Routes>
    </>
  )
}