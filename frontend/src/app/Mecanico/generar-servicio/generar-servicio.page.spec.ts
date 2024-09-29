import { ComponentFixture, TestBed } from '@angular/core/testing';
import { GenerarServicioPage } from './generar-servicio.page';

describe('GenerarServicioPage', () => {
  let component: GenerarServicioPage;
  let fixture: ComponentFixture<GenerarServicioPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(GenerarServicioPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
