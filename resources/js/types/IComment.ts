import { User } from '@/types/index';

export interface IComment {
    id: number;
    user_id: number;
    activity_id: number;
    content: string;
    created_at: string;
    updated_at: string;
    user: User;
}
