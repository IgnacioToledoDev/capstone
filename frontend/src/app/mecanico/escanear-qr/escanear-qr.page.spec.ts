import { ComponentFixture, TestBed } from '@angular/core/testing';
import { EscanearQrPage } from './escanear-qr.page';

describe('EscanearQrPage', () => {
  let component: EscanearQrPage;
  let fixture: ComponentFixture<EscanearQrPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(EscanearQrPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
