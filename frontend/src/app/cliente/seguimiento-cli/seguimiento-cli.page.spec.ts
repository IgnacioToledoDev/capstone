import { ComponentFixture, TestBed } from '@angular/core/testing';
import { SeguimientoCliPage } from './seguimiento-cli.page';

describe('SeguimientoCliPage', () => {
  let component: SeguimientoCliPage;
  let fixture: ComponentFixture<SeguimientoCliPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(SeguimientoCliPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
