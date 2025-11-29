import { User } from '@/types/index';
import { ILure } from '@/types/ILure';
import { ISpecie } from '@/types/ISpecie';
import { IComment } from '@/types/IComment';

export interface IActivities {
    id: number;
    user_id: number;
    specie: ISpecie;
    size: number;
    weight: number;
    lure: ILure;
    created_at: string;
    user: User;
    isFollowing: boolean;
    hasLiked: boolean;
    likesCount: number;
    commentsCount: number;
    comment: IComment;
}
