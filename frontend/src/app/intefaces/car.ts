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