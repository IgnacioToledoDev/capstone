export interface NewCarInterface {
  brand_id: number;
  model_id: number;
  patent: string;
  owner_id: number;
  year: number;
}

export interface CalendarEntry {
  id: number;
  car_id: number;
  start_maintenance: string;
  mechanic_id: number;
  description: string;
}

export interface CurrentUser {
  id: number;
  name: string;
  email: string;
}
export interface HistoricalEntry {
  id: number;
  name: string;
  status_id: number;
  recommendation_action: string;
  pricing: number;
  car_id: number;
  mechanic_id: number;
  start_maintenance: string;
  end_maintenance: string;
  car: {
    id: number;
    patent: string | null;
    brand: string;
    model: string;
    year: number;
    fullName: string;
  };
  owner: {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    username: string;
    lastname: string;
  };
}

export interface carViewInterface {
  id: number|null|undefined;
  marca: string;
  modelo: string;
  year: number;
  patente: string | undefined;
}
