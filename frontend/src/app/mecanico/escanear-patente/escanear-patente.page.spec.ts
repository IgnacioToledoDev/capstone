import { ComponentFixture, TestBed } from '@angular/core/testing';
import { EscanearPatentePage } from './escanear-patente.page';

describe('EscanearPatentePage', () => {
  let component: EscanearPatentePage;
  let fixture: ComponentFixture<EscanearPatentePage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(EscanearPatentePage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
