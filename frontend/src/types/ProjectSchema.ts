import z from "zod";
import { STATUS } from "./ProjectType";

  export const projectSchema = z.object({
    name: z.string().trim().min(1, { message: "Required" }),
    description: z.string().nullable(),
    status: z.enum(Object.values(STATUS), {message:"Invalid status"})
  });