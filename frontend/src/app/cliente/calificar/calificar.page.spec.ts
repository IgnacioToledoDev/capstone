import { ComponentFixture, TestBed } from '@angular/core/testing';
import { CalificarPage } from './calificar.page';

describe('CalificarPage', () => {
  let component: CalificarPage;
  let fixture: ComponentFixture<CalificarPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(CalificarPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
