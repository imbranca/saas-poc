import { LucideCircleUser, LucideLogOut, LucideUserRound } from "lucide-react";
import { useState } from "react";
import { Outlet, useNavigate } from "react-router-dom";
import { useAuth } from "./Context/AuthContext";
import { useMutation } from "@tanstack/react-query";


export default function Layout() {
  const { user,setUser }= useAuth();
  const navigate = useNavigate();
  const [userToggle, setUserToggle]= useState(false);

  async function fetchLogout(): Promise<any> {
    const res = await fetch('http://localhost:8000/api/logout',{
      method:'POST',
      credentials: 'include',
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' }
    });
    const json = await res.json();
    if (res.status !== 200) throw json;
    return json;
  }

  const logoutMutation = useMutation({
  mutationFn: fetchLogout,
  onSuccess: () => {
    setUser(null);
    navigate('/login');
  },
  onError: (error: Error) => {
    alert(error.message);
  }
});

  const logout = async ()=>{
    logoutMutation.mutate();
    navigate('/login');
  }

  return (
    <div className="">
      <nav className="p-5 flex justify-between border-bottom-black" >
        <div className="w-1/4 flex cursor-pointer" onClick={()=>{navigate('/dashboard')}}>
          <img src="/src/assets/home.svg"></img>
        </div>

        <div className="w-1/4 flex justify-end relative">
        <div className="nav-user flex w-full justify-end cursor-pointer" onClick={()=>{setUserToggle(!userToggle)}}>
            <span className="text-sm">Hello {user?.name}!</span>
            <LucideUserRound></LucideUserRound>
        </div>
          {userToggle && <div className="w-full flex flex-col border-black absolute bg-white top-8">
            <button className="hover:not-focus:bg-gray-200 cursor-pointer py-1 pl-2 flex" onClick={() => { navigate('/profile'); setUserToggle(false); }}>
              <LucideCircleUser className="my-auto mr-2"></LucideCircleUser>
              <span className="text-sm my-auto">Profile</span>
            </button>
            <button className="hover:not-focus:bg-gray-200 cursor-pointer py-1 pl-2 flex" onClick={logout}>
              <LucideLogOut className="my-auto mr-2"></LucideLogOut>
              <span className="text-sm my-auto">Logout</span>
            </button>
          </div>}
        </div>
      </nav>
      <div className="px-5 pb-5">
        <Outlet />
      </div>
    </div>
  );
}