import { useMutation, useQuery } from "@tanstack/react-query";
import { useEffect } from "react";
import { Controller, useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../../Context/AuthContext";
import z from "zod";
import { zodResolver } from "@hookform/resolvers/zod";

type Credentials = {
  email: string;
  password: string;
}

export default function Login(){
  const { setUser } = useAuth();
  const navigate = useNavigate();

  const loginSchema = z.object({
    email: z.email('Enter a valid email'),
    password: z.string().min(5, 'Minimum lenght 5 characters')
  })

  const { register, watch, handleSubmit,control, formState: { errors } } = useForm<Credentials>({
    defaultValues: {
      email: "",
      password: "Password#123"
    },
    resolver: zodResolver(loginSchema)
  });

  const loginMutation = useMutation({
    mutationFn: async ({ email, password }: { email: string, password: string }) => {
      const res = await fetch('http://localhost:8000/api/login', {
        method: 'POST',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ email, password }),
      });
      const json = await res.json();
      if (res.status !== 200) throw json;
      return json;
    },
    onSuccess: (res) => {
      console.log('Logged in:', res);
      setUser(res.data);
      navigate('/dashboard');
    },
    onError: (error) => {
      alert(error.message);
    },
  });

  async function onSubmit(data: any) {
    const {email, password} = data;
    loginMutation.mutate({ email, password });
  }

  return <>
      <div className="w-full flex mt-5">
        <div className="w-full border-black p-5 mx-4">
          <h4 className="mb-2">Login</h4>
          <form onSubmit={handleSubmit(onSubmit)}>
            <div className="formGroup">
              <input type="text" placeholder="Email" className="rounded-md text-sm border-black px-2"
                {
                ...register("email")
                } />
              { errors.email && (
                <div className="text-red-500 text-sm mt-1">{errors.email.message}</div>
              )}
            </div>
            <div className="formGroup mt-2">
              <input type="password" placeholder="Password" className="rounded-md text-sm border-black px-2"
                {
                ...register("password")
                } />
                {errors.password && (
                  <div className="text-red-500 text-sm mt-1">
                    {errors.password.message}
                  </div>
                )}
            </div>
            <button type="submit" className="px-2 mt-3 color-white border-black rounded-md text-sm cursor-point">Submit</button>
          </form>
        </div>
      </div>
  </>
}