export interface QuotationService {
    serviceId: number;
    isApproved: boolean;
  }
  
  export interface CreateQuotationRequest {
    carId: number;
    services: QuotationService[];
    status: boolean;
    approvedDateClient: string;
  }
  
  export interface CreateQuotationResponse {
    success: boolean;
    message: string;
    data?: any; // Adjust this as needed based on the response data structure 
  }
  
export  interface Quotation {
    car: {
      brand: string;
      model: string;
      patent: string;
      year: number;
    };
    mechanic: {
      id: number;
      name: string;
      email: string;
      phone: string | null;
      rut: string;
    };
    quotation: {
      id: number;
      approve_date_client: string;
      amount_services: number;
      total_price: number;
      approved_by_client: number;
    };
    content: Array<{
      details: {
        created_at: string;
        is_approved_by_client: number;
        quotation_id: number;
        service_id: number;
        updated_at: string;
      };
      service: {
        name: string;
        description: string;
        price: number;
      };
    }>;
  }