export enum GenderEnum {
    MALE = 'male',
    FEMALE = 'female',
}

export enum StoryStatusEnum {
    DRAFT = 'draft',
    AWAITING_EXTRACTING_CHAPTERS_REQUEST = 'awaiting-extracting-chapters-request',
    EXTRACTING_CHAPTERS = 'extracting-chapters',
    PUBLISHED = 'published',
}

export enum StoryRatingEnum {
    EVERYONE = 'everyone',
    TEEN = 'teen',
    YOUNG_ADULT = 'young-adult',
    MATURE = 'mature',
}

export enum ChapterStatusEnum {
    AWAITING_CREATOR_REVIEW = 'awaiting-creator-review',
    AWAITING_EXTRACTING_EVENTS_REQUEST = 'awaiting-extracting-events-request',
    EXTRACTING_EVENTS = 'extracting-events',
    WAITING_FOR_EVENT_PREPARATION = 'waiting-for-event-preparation',
    READY_TO_PLAY = 'ready-to-play',
    REJECTED = 'rejected',
}


