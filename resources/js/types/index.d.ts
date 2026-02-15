import { ChapterStatusEnum, GenderEnum, StoryRatingEnum, StoryStatusEnum } from '@/types/enum';

export interface UserInterface {
    id: number;
    first_name: string | null;
    last_name: string | null;
    full_name: string | null;
    gender: GenderEnum | null;
    username: string | null;
    email: string;
    avatar: string;
    bio: string | null;
}

export interface WriterInterface {
    id: number;
    first_name: string | null;
    last_name: string | null;
    full_name: string | null;
    username: string | null;
    avatar: string;
    bio: string | null;
    is_active: boolean;

    // Relations
    stories?: StoryInterface[];
}

export interface StoryInterface {
    id: number;
    title: string;
    teaser: string | null;
    status: StoryStatusEnum;
    rating: StoryRatingEnum;
    published_at: string | null;

    // Relations
    category?: CategoryInterface;
    writer?: WriterInterface;
    chapters?: ChapterInterface[];
}

export interface CategoryInterface {
    id: number;
    title: string;

    // Relations
    stories?: StoryInterface[];
}

export interface ChapterInterface {
    id: number;
    position: number;
    title: string;
    teaser: string | null;
    content: string | null;
    status: ChapterStatusEnum;

    // Relations
    story?: StoryInterface;
    events?: EventInterface[];
}

export interface EventInterface {
    id: number;
    position: number;
    title: string;
    content: string | null;
    objectives: string | null;
    attributes: string | null;

    // Relations
    chapter?: ChapterInterface;
}
