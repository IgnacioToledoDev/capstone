import { ComponentFixture, TestBed } from '@angular/core/testing';
import { AprobarCotizaPage } from './aprobar-cotiza.page';

describe('AprobarCotizaPage', () => {
  let component: AprobarCotizaPage;
  let fixture: ComponentFixture<AprobarCotizaPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(AprobarCotizaPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
