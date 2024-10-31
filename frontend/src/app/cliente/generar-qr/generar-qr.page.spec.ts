import { ComponentFixture, TestBed } from '@angular/core/testing';
import { GenerarQrPage } from './generar-qr.page';

describe('GenerarQrPage', () => {
  let component: GenerarQrPage;
  let fixture: ComponentFixture<GenerarQrPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(GenerarQrPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
