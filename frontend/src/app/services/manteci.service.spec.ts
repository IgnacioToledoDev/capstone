import { TestBed } from '@angular/core/testing';

import { ManteciService } from './manteci.service';

describe('ManteciService', () => {
  let service: ManteciService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ManteciService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
