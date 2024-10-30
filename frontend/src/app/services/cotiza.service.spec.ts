import { TestBed } from '@angular/core/testing';

import { CotizaService } from './cotiza.service';

describe('CotizaService', () => {
  let service: CotizaService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(CotizaService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
