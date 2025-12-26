export interface ISpot {
    id: number;
    user_id: number;
    latitude: number;
    longitude: number;
    name: string;
    license: string;
    url: string;
    species: { id: number; name: string }[];
    environement: string[];
    rules: string[];
    equipments: string[];
    is_public: boolean;
}
