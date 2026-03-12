export const STATUS = {
  Active: 'active',
  Draft: 'draft',
  Archived: 'archived',
} as const;

export type ProjectStatus = typeof STATUS[keyof typeof STATUS];

export type Project = {
  id: number;
  name: string;
  description: string | null;
  status: ProjectStatus;
  created_by: number;
};