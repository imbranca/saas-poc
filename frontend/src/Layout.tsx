import { LucideCircleUser, LucideLogOut, LucideUserRound } from "lucide-react";
import { useState } from "react";
import { Outlet, useNavigate } from "react-router-dom";


export default function Layout() {
  const navigator = useNavigate();
  const [userToggle, setUserToggle]= useState(false);

  async function fetchLogout(): Promise<any> {
    const res = await fetch('http://localhost:8000/api/logout',{
      method:'GET',
      credentials: 'include',
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' }
    });
    if (res.status !== 200) throw new Error('Failed logout');
    const json = await res.json();
    return json.data;
  }

  return (
    <div className="">
      <nav className="p-5 flex justify-between border-bottom-black" >
        <div className="w-1/4 flex" onClick={()=>{navigator('/dashboard')}}>
          <img src="src/assets/home.svg"></img>
        </div>

        <div className="w-1/4 flex justify-end relative">
        <div className="nav-user flex w-full justify-end" onClick={()=>{setUserToggle(!userToggle)}}>
            User
            <LucideUserRound></LucideUserRound>
        </div>
          { userToggle && <div className="w-full flex flex-col border-black absolute bg-white top-8">
            <button className="py-1 pl-2 border-bottom-black flex" onClick={()=>{ navigator('/profile') }}>
              <LucideCircleUser className="my-auto mr-2"></LucideCircleUser>
              <span className="text-sm my-auto">Profile</span>
            </button>
            <button className="py-1 pl-2 flex">
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