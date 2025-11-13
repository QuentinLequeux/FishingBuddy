import { User } from '@/types/index';

export interface IActivities {
    id: number;
    user_id: number;
    specie: string;
    size: number;
    weight: number;
    lure: string;
    created_at: string;
    user: User;
    isFollowing: boolean;
}
