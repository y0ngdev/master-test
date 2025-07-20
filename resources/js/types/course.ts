export type Lesson = {
    id: string;
    title: string;
    description: string;
    duration: string;
    canWatch: boolean;
    url: string;
    streamURL: string;
    moduleID: number;
};

export type Module = {
    id: string;
    title: string;
    lessons: Lesson[];
};
