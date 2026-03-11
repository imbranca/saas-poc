import { Route, Routes } from "react-router-dom";
import Login from "./features/login/Login";
import Layout from "./Layout";
import Dashboard from "./features/dashboard/Dashboard";
import Profile from "./features/profile/Profile";
import ProjectDetail from "./features/dashboard/EditProject";
import Project from "./features/dashboard/CreateProject";
import ProtectedRoute from "./Component/ProtectRoute";
import NotFound from "./Component/NotFound";
import ManagerRoute from "./Component/ManagerRoute";


export default function AppRoutes(){
  return(
    <>
      <Routes>
        <Route element={<ProtectedRoute />}>
          <Route element={<Layout />}>
            <Route path='/dashboard' element={<Dashboard />}></Route>
            <Route element={<ManagerRoute/>}>
              <Route path='/project/edit/:id' element={<ProjectDetail />}></Route>
              <Route path='/project/create' element={<Project />}></Route>
            </Route>
            <Route path='/profile' element={<Profile />}></Route>
          </Route>
        </Route>
          <Route path="*" element={<NotFound />}></Route>
          <Route path='/login' element={<Login />}></Route>
      </Routes>
    </>
  )
}