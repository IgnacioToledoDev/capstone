import { ComponentFixture, TestBed } from '@angular/core/testing';
import { NuevaContrasenaPage } from './nueva-contrasena.page';

describe('NuevaContrasenaPage', () => {
  let component: NuevaContrasenaPage;
  let fixture: ComponentFixture<NuevaContrasenaPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(NuevaContrasenaPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
