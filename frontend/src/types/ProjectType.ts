export type ProjectStatus = 'active' | 'archived' | 'draft';

export type Project = {
  id: number;
  name: string;
  description: string | null;
  status: ProjectStatus;
  created_by: number;
};