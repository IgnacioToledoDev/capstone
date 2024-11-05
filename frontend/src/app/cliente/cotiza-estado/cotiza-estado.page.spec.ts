import { ComponentFixture, TestBed } from '@angular/core/testing';
import { CotizaEstadoPage } from './cotiza-estado.page';

describe('CotizaEstadoPage', () => {
  let component: CotizaEstadoPage;
  let fixture: ComponentFixture<CotizaEstadoPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(CotizaEstadoPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
