import { useQuery } from "@tanstack/react-query";

export default function Profile(){


  async function fetchUser(): Promise<any>{
    const res = await fetch('http://localhost:8000/api/me',
      {
        method: 'GET',
        credentials: 'include',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' }
      }
    );
    const json = await res.json();
    if(res.status !== 200) throw json;
    return json.data;
  }

  const { data, isLoading, isError}=useQuery({
    queryKey:['user'],
    queryFn: fetchUser,
    retry: false
  });

  if(isError) return <p className="mt-4">Something went wrong. Please try again.</p>;
  return(
    <>
    <div className="wrapper">
      <div className="user-info mt-5">
        <h2>Name: {data?.name}</h2>
        <h3>Email: {data?.email}</h3>
      </div>
    </div>
    </>
  )
}