export enum GenderEnum {
    MALE = 'male',
    FEMALE = 'female',
}

export interface UserInterface {
    id: numbe;
    first_name: string | null;
    last_name: string | null;
    full_name: string | null;
    gender: GenderEnum | null;
    nickname: string | null;
    username: string | null;
    email: string;
    avatar: string | null;
    bio: string | null;
}
