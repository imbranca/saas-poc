import { Navigate, Outlet } from "react-router-dom";
import { useAuth } from "../Context/AuthContext";

export default function ManagerRoute() {
  const { user } = useAuth();

  if (user && user.role !== 'manager')
    return <Navigate to="/dashboard" replace />;

  return <Outlet />;
}